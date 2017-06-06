<?php

namespace LinusShops\Kickbox\Controller\Deliverable;

use LinusShops\Common\Model\JsonResponseBuilder;
use LinusShops\Kickbox\Model\EmailVerifier;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\ResponseInterface;

/**
 * Ajax endpoint for validating an email against Kickbox.
 *
 * @author Sam Schmidt <samuel@dersam.net>
 */
class Index extends Action
{
    /**
     * The fluent standard json format response builder.
     * @var \LinusShops\Common\Model\JsonResponseBuilder
     */
    protected $json;

    /**
     * Email verification handler.
     * @var \LinusShops\Kickbox\Model\EmailVerifier
     */
    private $emailVerifier;

    /**
     * Index constructor.
     *
     * @param Context $context
     * @param JsonResponseBuilder $json
     * @param EmailVerifier $emailVerifier
     */
    public function __construct(
        Context $context,
        JsonResponseBuilder $json,
        EmailVerifier $emailVerifier
    ) {
        $this->json = $json;
        $this->emailVerifier = $emailVerifier;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $email = $this->getRequest()->getParam('email');
        if (empty($email)) {
            $this->json->setFeedbackMessage(__("No email provided."));
        } else {
            $this->json->setPayload([
                'email' => $email,
                'deliverable' => $this->emailVerifier->verifyIsDeliverable($email)
            ]);
        }

        /** @var Http $response */
        $response = $this->getResponse();
        return $this->json->sendResponseJson($response);
    }
}
