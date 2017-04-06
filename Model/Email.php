<?php

namespace LinusShops\Kickbox\Model;

use Kickbox\Client;
use Kickbox\ClientFactory;
use Kickbox\Exception\ClientException;
use Magento\Framework\App\Config\ScopeConfigInterface;

/**
 * Provides a Magento-friendly wrapper for the Kickbox API.
 * The magic methods all represent the fields available from a response from
 * Kickbox.
 *
 * @author Sam Schmidt <samuel@dersam.net>
 *
 * {{magicdoc_start}}
 * @method string result(...$parameters)
 * @method string reason(...$parameters)
 * @method boolean role(...$parameters)
 * @method boolean free(...$parameters)
 * @method boolean disposable(...$parameters)
 * @method boolean accept_all(...$parameters)
 * @method string did_you_mean(...$parameters)
 * @method integer sendex(...$parameters)
 * @method string email(...$parameters)
 * @method string user(...$parameters)
 * @method string domain(...$parameters)
 * @method boolean success(...$parameters)
 * @method string message(...$parameters)
 * {{magicdoc_end}}
 */

class Email
{
    private $email;
    private $kx_response;
    private $clientFactory;
    private $scopeConfig;

    public function __construct(
        ClientFactory $clientFactory,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->clientFactory = $clientFactory;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $emailAddress
     * @return $this
     */
    public function load($emailAddress)
    {
        $this->email = $emailAddress;
        return $this;
    }

    /**
     * Query kickbox on the validity of the email contained in this object.
     *
     * @param array $options
     * @return $this
     */
    public function verify($options = array('timeout' => 6000))
    {
        /** @var Client $client */
        $client = $this->clientFactory->create([
            'auth' => $this->scopeConfig->getValue('linusshops_kickbox/api/key')
        ]);

        $kickbox = $client->kickbox();

        try {
            $this->kx_response = $kickbox->verify($this->email, $options);
        } catch (ClientException $ex) {
            Mage::logException($ex);
        }

        return $this;
    }

    /**
     * Look up the given method name in the Kickbox response fields
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return (!empty($this->kx_response->body[$name]))
            ? $this->kx_response->body[$name]
            : null;
    }

    /**
     * Is the email deliverable?
     *
     * `deliverable` and `risky` both return `true`. If there is an
     * `Insufficient balance` or any other exception thrown in the `verify`
     * method, this returns `null`.
     *
     * @return bool|null
     */
    public function isDeliverable()
    {
        return (is_string($this->result()))
            ? in_array($this->result(), array('deliverable', 'risky', 'unknown'))
            : null;

    }
}
