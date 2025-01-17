<?php

namespace Phalcon\UserPlugin\Models\User;

use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * Phalcon\UserPlugin\Models\User\UserResetPasswords.
 */
class UserResetPasswords extends \Phalcon\Mvc\Model
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $user_id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var string
     */
    protected $created_at;

    /**
     * @var string
     */
    protected $updated_at;

    /**
     * @var int
     */
    protected $reset;

    /**
     * Method to set the value of field id.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Method to set the value of field user_id.
     *
     * @param int $user_id
     *
     * @return $this
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Method to set the value of field code.
     *
     * @param string $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Method to set the value of field created_at.
     *
     * @param string $created_at
     *
     * @return $this
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Method to set the value of field updated_at.
     *
     * @param string $updated_at
     *
     * @return $this
     */
    public function setUpdatedAt($updated_at)
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Method to set the value of field reset.
     *
     * @param int $reset
     *
     * @return $this
     */
    public function setReset($reset)
    {
        $this->reset = $reset;

        return $this;
    }

    /**
     * Returns the value of field id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the value of field user_id.
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Returns the value of field code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Returns the value of field created_at.
     *
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Returns the value of field updated_at.
     *
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Returns the value of field reset.
     *
     * @return int
     */
    public function getReset()
    {
        return $this->reset;
    }

    /**
     * @return UserResetPasswords[]
     */
    public static function find($parameters = array()): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * @return UserResetPasswords
     */
    public static function findFirst($parameters = array()): \Phalcon\Mvc\ModelInterface | null
    {
        return parent::findFirst($parameters);
    }

    public function initialize()
    {
        $this->belongsTo('user_id', 'Phalcon\UserPlugin\Models\User\User', 'id', array(
            'alias' => 'user',
        ));

        $this->setSource('user_reset_passwords');
    }

    /**
     * Before create the user assign a password.
     */
    public function beforeValidationOnCreate()
    {
        //Timestamp the confirmaton
        $this->created_at = date('Y-m-d H:i:s');

        //Generate a random confirmation code
        $this->code = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(24)));

        //Set status to non-confirmed
        $this->reset = 0;
    }

    /**
     * Send an e-mail to users allowing him/her to reset his/her password.
     */
    public function afterCreate()
    {
        $this->getDI()->getMail()->send(
            array(
                $this->user->getEmail() => $this->user->getName() ? $this->user->getName() : 'Customer',
            ),
            'Reset your password',
            'reset',
            array(
                'resetUrl' => '/user/resetPassword/'.$this->getCode().'/'.$this->user->getEmail(),
            )
        );
    }

    /**
     * Sets the timestamp before update the confirmation.
     */
    public function beforeValidationOnUpdate()
    {
        //Timestamp the confirmaton
        $this->updated_at = date('Y-m-d H:i:s');
    }
}
