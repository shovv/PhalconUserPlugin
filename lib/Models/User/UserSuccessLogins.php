<?php

namespace Phalcon\UserPlugin\Models\User;

use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * Phalcon\UserPlugin\Models\User\UserSuccessLogins.
 */
class UserSuccessLogins extends \Phalcon\Mvc\Model
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
    protected $ip_address;

    /**
     * @var string
     */
    protected $user_agent;

    /**
     * @var string
     */
    protected $created_at;

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
     * Method to set the value of field ip_address.
     *
     * @param string $ip_address
     *
     * @return $this
     */
    public function setIpAddress($ip_address)
    {
        $this->ip_address = $ip_address;

        return $this;
    }

    /**
     * Method to set the value of field user_agent.
     *
     * @param string $user_agent
     *
     * @return $this
     */
    public function setUserAgent($user_agent)
    {
        $this->user_agent = $user_agent;

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
     * Returns the value of field ip_address.
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ip_address;
    }

    /**
     * Returns the value of field user_agent.
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->user_agent;
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

    public function initialize()
    {
        $this->belongsTo('user_id', 'Phalcon\UserPlugin\Models\User\User', 'id', array(
            'alias' => 'user',
            'reusable' => true,
        ));

        $this->setSource('user_success_logins');
    }

    /**
     * @return UserSuccessLogins[]
     */
    public static function find($parameters = array()): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * @return UserSuccessLogins
     */
    public static function findFirst($parameters = array()): \Phalcon\Mvc\ModelInterface | null
    {
        return parent::findFirst($parameters);
    }

    public function beforeValidationOnCreate()
    {
        $this->created_at = date('Y-m-d H:i:s'); // Don't use mysql server time, but use application's timezone
    }
}
