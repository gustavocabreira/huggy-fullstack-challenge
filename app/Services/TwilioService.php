<?php

namespace App\Services;
use Twilio\Base\BaseClient;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;
use Twilio\Rest\Client;
use Twilio\TwiML\VoiceResponse;

class TwilioService
{
    protected BaseClient $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            username: config('services.twilio.account_sid'),
            password: config('services.twilio.auth_token'),
        );
    }

    public function handleTwilioWebhook($numberToCall): VoiceResponse
    {
        $response = new VoiceResponse();

        $numberToCall = sprintf('+%s', $numberToCall);

        $attributes = [
            'callerId' => config('services.twilio.number'),
            'record' => 'true',
        ];

        $response->dial(
            number: $numberToCall,
            attributes: $attributes
        );

        return $response;
    }

    public function generateToken(): string
    {
        $token = new AccessToken(
            accountSid: config('services.twilio.account_sid'),
            signingKeySid: config('services.twilio.api_key_sid'),
            secret: config('services.twilio.api_key_secret'),
            ttl: 3600,
            identity: auth()->user()->id
        );

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid(config('services.twilio.app_sid'));
        $voiceGrant->setIncomingAllow(true);
        $token->addGrant($voiceGrant);

        return $token->toJWT();
    }
}
