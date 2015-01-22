<?php

namespace Omeka\View\Strategy;

use Omeka\Api\Exception as ApiException;
use Omeka\Api\Response;
use Omeka\View\Model\ApiJsonModel;
use Omeka\View\Renderer\ApiJsonRenderer;
use Zend\Json\Exception as JsonException;
use Zend\View\Strategy\JsonStrategy;
use Zend\View\ViewEvent;

/**
 * View strategy for returning JSON from the API.
 */
class ApiJsonStrategy extends JsonStrategy
{
    /**
     * Constructor, sets the renderer object
     *
     * @param \Omeka\View\Renderer\ApiJsonRenderer
     */
    public function __construct(ApiJsonRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    /**
     * {@inheritDoc}
     */
    public function selectRenderer(ViewEvent $e)
    {
        $model = $e->getModel();

        if (!$model instanceof ApiJsonModel) {
            // no JsonModel; do nothing
            return;
        }

        // JsonModel found
        return $this->renderer;
    }

    /**
     * {@inheritDoc}
     */
    public function injectResponse(ViewEvent $e)
    {
        // Test this again here to avoid running our extra code for non-API
        // requests.
        $renderer = $e->getRenderer();
        if ($renderer !== $this->renderer) {
            // Discovered renderer is not ours; do nothing
            return;
        }

        parent::injectResponse($e);

        $model = $e->getModel();
        $apiResponse = $model->getApiResponse();

        $statusCode = $model->getOption('status_code');
        if ($statusCode === null) {
            $statusCode = $this->getResponseStatusCode($apiResponse);
        }
        $e->getResponse()->setStatusCode($statusCode);
    }

    /**
     * Get the HTTP status code for an API response.
     *
     * @param \Omeka\Api\Response $response
     * @return integer
     */
    protected function getResponseStatusCode(Response $response)
    {
        switch ($response->getStatus()) {
            case Response::SUCCESS:
                if (null === $response->getContent()) {
                    return 204; // No Content
                }
                return 200; // OK
            case Response::ERROR_VALIDATION:
                return 422; // Unprocessable Entity
            case Response::ERROR:
            default:
                return $this->getStatusCodeForException($response->getException());
        }
    }

    /**
     * Get a status code based on the type of an exception (or lack thereof).
     *
     * @param \Exception|null $exception
     * @return integer
     */
    protected function getStatusCodeForException(\Exception $exception = null)
    {
        if ($exception instanceof JsonException\RuntimeException) {
            return 400; // Bad Request
        } else if ($exception instanceof ApiException\PermissionDeniedException) {
            return 403; // Forbidden
        } else if ($exception instanceof ApiException\NotFoundException) {
            return 404; // Not Found
        } else {
            return 500; // Internal Server Error
        }
    }
}