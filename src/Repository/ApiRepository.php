<?php

namespace App\Repository;

use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class ApiRepository
{
    /**
     * @param $url
     * @param $mode
     * @return string|null
     * @throws Exception
     */
    public static function rmfAudioXml($url, $mode) : string | null
    {
        /// TRY FOR XML
        try {
            $xml = self::getXML($url);
        } catch (ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface $e) {
            // continue for now
            return new Response('Exception occured');
        }

        /// DECODE XML TO ARRAY
        $encoder = new XmlEncoder();
        $data = $encoder->decode($xml, 'xml');

        /// SELECT AUDIO MODE
        switch ($mode) {
            case 'aac':
                $group1 = 'playlist';
                $group2 = 'item';
                break;
            case 'mp3':
                $group1 = 'playlistMp3';
                $group2 = 'item_mp3';
                break;
            default:
                return new Response('Audio mode not supported');
        }

        /// SUBSELECT GROUP, COUNT AND SELECT RANDOM
        $data = $data[$group1][$group2];
        $c = count($data);
        $r = random_int(0, $c - 1);

        /// RETURN DATA
        return $data[$r]['#'];
    }

    /**
     * @param string $url
     * @return string|null
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private static function getXML(string $url): ?string
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);
        if ($response->getStatusCode() === 200) {
            return $response->getContent();
        }
        return null;
    }

    /**
     * @throws Exception
     * @return string
     */
    public static function guidv4() : string {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = random_bytes(16);
        assert(strlen($data) === 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}