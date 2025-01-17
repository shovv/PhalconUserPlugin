<?php

namespace Phalcon\UserPlugin\Models\User;

use Phalcon\Mvc\Model\ResultsetInterface;

/**
 * Phalcon\UserPlugin\Models\User\UserRememberTokens.
 */
class UserRememberTokens extends \Phalcon\Mvc\Model
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
    protected $token;

    /**
     * @var string
     */
    protected $user_agent;

    /**
     * @var int
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
     * Method to set the value of field token.
     *
     * @param string $token
     *
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;

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
     * @param int $created_at
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
     * Returns the value of field token.
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
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
     * @return int
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

        $this->setSource('user_remember_tokens');
    }

    /**
     * @return UserRememberTokens[]
     */
    public static function find($parameters = array()): ResultsetInterface
    {
        return parent::find($parameters);
    }

    /**
     * @return UserRememberTokens
     */
    public static function findFirst($parameters = array()): \Phalcon\Mvc\ModelInterface | null
    {
        return parent::findFirst($parameters);
    }
}
