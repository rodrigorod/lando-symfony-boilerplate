<?php

declare(strict_types=1);

namespace App\Controller;

use App\DependencyInjection\SerializerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

abstract class EndpointController
{
    use SerializerAwareTrait;

    /**
     * Get content from request.
     *
     * @param Request $request
     *   Incoming request
     * @param bool $isArray
     *   Define return type as array or string
     *
     * @return array|resource|string
     *   Request content
     */
    protected function getContentFromRequest(Request $request, bool $isArray = true): mixed
    {
        if (false === $isArray) {
            return $request->getContent();
        }

        $requestContent = $request->toArray();

        if (empty($requestContent)) {
            throw new BadRequestHttpException('Request body is required.');
        }

        return $requestContent;
    }

    /**
     * Build entity response.
     *
     * @param mixed $entity
     *   Entity to validate and return
     * @param Request $request
     *   Incoming request
     * @param array<string, mixed> $cacheContext
     *   Cache context
     * @param array<string> $groups
     *   Serializing groups
     */
    protected function buildEntityResponse(mixed $entity, Request $request, array $cacheContext = [], array $groups = ['view']): Response
    {
        $content = $this->serializer->serialize(
            $entity,
            'json',
            [
                'groups' => $groups,
            ]
        );

        return $this->buildResponse($content, $request, $cacheContext);
    }

    /**
     * Build response.
     *
     * @param string $content
     *   Response content already serialized and validated
     * @param Request $request
     *   Incoming request
     * @param array<string, mixed> $cacheContext
     *   Cache context
     */
    protected function buildResponse(string $content, Request $request, array $cacheContext = []): Response
    {
        $response = new Response($content, Response::HTTP_OK, [
            'Content-Type' => 'application/json',
        ]);

        if (!empty($cacheContext) && isset($cacheContext['private']) && true === $cacheContext['private']) {
            $response->headers->addCacheControlDirective('no-cache');
            $response->headers->addCacheControlDirective('no-store');
            $response->headers->set('pragma', 'no-cache');
            $response->headers->set('expires', '0');
        }

        $response->setCache($cacheContext + [
            'etag' => sha1($content),
        ]);

        $response->headers->set('Strict-Transport-Security', 'max-age=16070400');

        $response->isNotModified($request);

        return $response;
    }

    /**
     * Build accepted response.
     *
     * @param null|string $content
     *   Response content
     */
    protected function buildAcceptedResponse(string $content = null): Response
    {
        return new Response($content, Response::HTTP_ACCEPTED, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Build empty response.
     */
    protected function buildEmptyResponse(): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Build not found response.
     */
    protected function buildNotFoundResponse(?string $content = null): Response
    {
        return new Response($content, Response::HTTP_NOT_FOUND, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Build unauthorized response.
     */
    protected function buildUnauthorizedResponse(?string $content = null): Response
    {
        return new Response('Unauthorized', Response::HTTP_UNAUTHORIZED, [
            'Content-Type' => 'application/json',
        ]);
    }
}
