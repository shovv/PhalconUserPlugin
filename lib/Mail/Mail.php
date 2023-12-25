<?php

namespace Phalcon\UserPlugin\Mail;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\File;

use Phalcon\Di\Injectable as Component;
use Phalcon\Mvc\View;

// @TODO Upgrade to Symfony Mailer

/**
 * Phalcon\UserPlugin\Mail\Mail.
 *
 * Sends e-mails based on pre-defined templates
 */
class Mail extends Component
{
    protected $_transport;

    protected $_message;

    protected $_sender;

    protected $_recipient;

    protected $_mailer;

    protected $_directSmtp = true;

    protected $attachments = [];

    protected $images = [];

    protected $mailSettings;

    /**
     * Initialize required components
     */
    public function init()
    {
        $this->mailSettings = $this->config->mail;

        $this->_message = new Email();
        $this->addTransport();
        $this->addSender();

        $this->_mailer = new Mailer($this->_transport);
    }

    /**
     * Adds a mail transport
     */
    private function addTransport()
    {
        if (!$this->_transport) {
            $dsn = sprintf(
                'smtp://%s:%s@%s:%d',
                $this->mailSettings->smtp->username,
                $this->mailSettings->smtp->password,
                $this->mailSettings->smtp->server,
                $this->mailSettings->smtp->port,
            );

            $this->_transport = Transport::fromDsn($dsn);
        }
    }

    /**
     * Adds a email sender
     */
    private function addSender()
    {
        if(!$this->_sender) {
            $this->_sender = new Address(
                $this->mailSettings->fromEmail,
                $this->mailSettings->fromName
            );
        }
        
    }

    /**
     * Adds a email recipient
     *
     * @param string $name
     * @param string $email
     */
    public function addRecipient($email, $name)
    {
        $this->_recipient = new Address($email, $name);
    }

    /**
     * Adds a new file to attach.
     *
     * @param unknown $file
     */
    public function addAttachment($name, $content, $type = 'text/plain')
    {
        $this->attachments[] = array(
            'name' => $name,
            'content' => $content,
            'type' => $type,
        );
    }

    /**
     * Applies a template to be used in the e-mail.
     *
     * @param string $name
     * @param array  $params
     */
    public function getTemplate($message, $name, $params)
    {
        $parameters = array_merge(array(
            'publicUrl' => $this->config->application->publicUrl,
        ), $params);

        foreach ($this->images as $name => $image_path) {
            $parameters[$name] = $message->embed(\Swift_Image::fromPath($image_path));
        }

        return $this->view->getRender('emailTemplates', $name, $parameters, function ($view) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        });
    }

    /**
     * Inserts images without using getTemplate.
     *
     * @param string $message
     * @param string $content
     *
     * @return mixed
     */
    public function insertImages($message, $content)
    {
        foreach ($this->images as $name => $image_path) {
            $image_embed = $message->embed(\Swift_Image::fromPath($image_path));
            $content = str_replace(rawurlencode('{{ '.$name.' }}'), $image_embed, $content);
        }

        return $content;
    }

    /**
     * Get the instance of \Mailer
     *
     * @return \Mailer
     */
    public function getMailer()
    {
        return $this->_mailer;
    }

    /**
     * Ge instance of \Email
     *
     * @return \Email
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * Sends e-mails based on predefined templates. If the $body param
     * has value, the template will be ignored.
     *
     * @param array  $to
     * @param string $subject
     * @param string $name    Template name
     * @param array  $params
     * @param array  $body
     */
    public function send($subject, $name = null, $params = null, $body = null)
    {
        //Images
        if (isset($params['images'])) {
            $this->images = $params['images'];
        }

        if (null === $body) {
            $template = $this->getTemplate($this->_message, $name, $params);
        } else {
            $template = $this->insertImages($this->_message, $body);
        }

         // Setting message params
        $this->_message->from($this->_sender)
            ->to($this->_recipient)
            ->subject($subject)
            ->html($template);

        // Check attachments to add
        foreach ($this->attachments as $file) {
            $this->_message->addPart(
                new DataPart(
                    new File($file['content']),
                    $file['name'],
                    $file['type']
                )
            );
        }

        $result = $this->_mailer->send($this->_message);

        $this->_transport->stop();
        $this->attachments = array();

        return $result;
    }
}
