<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Bus;
use Laravel\Reverb\Channels\ChannelBroker;
use Laravel\Reverb\Contracts\ApplicationProvider;
use Laravel\Reverb\Contracts\ChannelManager;
use Laravel\Reverb\Contracts\ConnectionManager;
use Laravel\Reverb\Jobs\PingInactiveConnections;
use Laravel\Reverb\Jobs\PruneStaleConnections;
use Laravel\Reverb\Servers\ApiGateway\Request;
use Laravel\Reverb\Servers\ApiGateway\Server;
use Laravel\Reverb\Tests\ApiGatewayTestCase;

// uses(ApiGatewayTestCase::class);

// beforeEach(function () {
//     Bus::fake();
// });

// afterEach(function () {
//     connectionManager()->flush();
//     channelManager()->flush();
// });

// it('can handle a new connection', function () {
//     $this->connect();

//     $this->assertCount(1, connectionManager()->all());
// })->skip();

// it('can handle multiple new connections', function () {
//     $this->connect();
//     $this->connect('def-456');

//     $this->assertCount(2, connectionManager()->all());
// })->skip();

// it('can handle connections to different applications', function () {
//     $this->connect();
//     $this->connect(appKey: 'pusher-key-2');

//     foreach (App::make(ApplicationProvider::class)->all() as $app) {
//         $this->assertCount(1, connectionManager()->for($app)->all());
//     }
// })->skip();

// it('can subscribe to a channel', function () {
//     $this->subscribe('test-channel');

//     $this->assertCount(1, connectionManager()->all());

//     $this->assertCount(1, channelManager()->connectionKeys(ChannelBroker::create('test-channel')));

//     $this->assertSent('abc-123', '{"event":"pusher_internal:subscription_succeeded","channel":"test-channel"}');
// })->skip();

// it('can subscribe to a private channel', function () {
//     $this->subscribe('private-test-channel');

//     $this->assertSent('abc-123', '{"event":"pusher_internal:subscription_succeeded","channel":"private-test-channel"}');
// })->skip();

// it('can subscribe to a presence channel', function () {
//     $data = ['user_id' => 1, 'user_info' => ['name' => 'Test User']];
//     $this->subscribe('presence-test-channel', data: $data);

//     $this->assertSent('abc-123', [
//         'pusher_internal:subscription_succeeded',
//         '"hash\":{\"1\":{\"name\":\"Test User\"}}',
//     ]);
// })->skip();

// it('can notify other subscribers of a presence channel when a new member joins', function () {
//     $data = ['user_id' => 1, 'user_info' => ['name' => 'Test User 1']];
//     $this->subscribe('presence-test-channel', data: $data);

//     $data = ['user_id' => 2, 'user_info' => ['name' => 'Test User 2']];
//     $this->subscribe('presence-test-channel', data: $data, connectionId: 'def-456');
//     $this->assertSent('abc-123', '{"event":"pusher_internal:member_added","data":{"user_id":2,"user_info":{"name":"Test User 2"}},"channel":"presence-test-channel"}');

//     $data = ['user_id' => 3, 'user_info' => ['name' => 'Test User 3']];
//     $this->subscribe('presence-test-channel', data: $data, connectionId: 'ghi-789');
//     $this->assertSent('def-456', '{"event":"pusher_internal:member_added","data":{"user_id":3,"user_info":{"name":"Test User 3"}},"channel":"presence-test-channel"}');
// })->skip();

// it('can notify other subscribers of a presence channel when a member leaves', function () {
//     $this->withoutExceptionHandling();
//     $data = ['user_id' => 1, 'user_info' => ['name' => 'Test User 1']];
//     $this->subscribe('presence-test-channel', data: $data);

//     $data = ['user_id' => 2, 'user_info' => ['name' => 'Test User 2']];
//     $this->subscribe('presence-test-channel', data: $data, connectionId: 'def-456');
//     $this->assertSent('abc-123', '{"event":"pusher_internal:member_added","data":{"user_id":2,"user_info":{"name":"Test User 2"}},"channel":"presence-test-channel"}');

//     $data = ['user_id' => 3, 'user_info' => ['name' => 'Test User 3']];
//     $this->subscribe('presence-test-channel', data: $data, connectionId: 'ghi-789');
//     $this->assertSent('def-456', '{"event":"pusher_internal:member_added","data":{"user_id":3,"user_info":{"name":"Test User 3"}},"channel":"presence-test-channel"}');

//     $this->disconnect('ghi-789');

//     $this->assertSent(
//         message: '{"event":"pusher_internal:member_removed","data":{"user_id":3},"channel":"presence-test-channel"}',
//         times: 2
//     );
// })->skip();

// it('can receive a message broadcast from the server', function () {
//     $this->subscribe('test-channel');
//     $this->subscribe('test-channel', connectionId: 'def-456');
//     $this->subscribe('test-channel', connectionId: 'ghi789');

//     $this->post('apps/123456/events', [
//         'name' => 'App\\Events\\TestEvent',
//         'channel' => 'test-channel',
//         'data' => ['foo' => 'bar'],
//     ])->assertOk();

//     $this->assertSent(message: '{"event":"App\\\\Events\\\\TestEvent","channel":"test-channel","data":{"foo":"bar"}}');
// })->skip();

// it('can handle an event', function () {
//     $this->subscribe('presence-test-channel', data: ['user_id' => 1, 'user_info' => ['name' => 'Test User 1']]);

//     $this->post('apps/123456/events', [
//         'name' => 'App\\Events\\TestEvent',
//         'channel' => 'presence-test-channel',
//         'data' => ['foo' => 'bar'],
//     ])->assertOk();

//     $this->assertSent('abc-123', message: '{"event":"App\\\\Events\\\\TestEvent","channel":"presence-test-channel","data":{"foo":"bar"}}');
// })->skip();

// it('can respond to a ping', function () {
//     $this->send(['event' => 'pusher:ping']);

//     $this->assertSent('abc-123', '{"event":"pusher:pong"}', 1);
// })->skip();

// it('it can ping inactive subscribers', function () {
//     $this->connect();

//     Carbon::setTestNow(now()->addMinutes(10));

//     (new PingInactiveConnections)->handle(
//         connectionManager()
//     );

//     $this->assertSent('abc-123', '{"event":"pusher:ping"}', 1);
// })->skip();

// it('it can disconnect inactive subscribers', function () {
//     $this->subscribe('test-channel');

//     expect(connectionManager()->all())->toHaveCount(1);
//     expect(channelManager()->connectionKeys(ChannelBroker::create('test-channel')))->toHaveCount(1);

//     Carbon::setTestNow(now()->addMinutes(10));

//     (new PingInactiveConnections)->handle(
//         connectionManager()
//     );
//     $this->assertSent('abc-123', '{"event":"pusher:ping"}');

//     (new PruneStaleConnections)->handle(
//         connectionManager(),
//         channelManager()
//     );

//     expect(connectionManager()->all())->toHaveCount(0);
//     expect(channelManager()->connectionKeys(ChannelBroker::create('test-channel')))->toHaveCount(0);

//     $this->assertSent('abc-123', '{"event":"pusher:error","data":"{\"code\":4201,\"message\":\"Pong reply not received in time\"}"}', 1);
// })->skip();

// it('can handle a client whisper', function () {
//     $this->subscribe('test-channel');

//     $this->subscribe('test-channel', connectionId: 'def-456');

//     $this->send([
//         'event' => 'client-start-typing',
//         'channel' => 'test-channel',
//         'data' => [
//             'id' => 123,
//             'name' => 'Joe Dixon',
//         ],
//     ], 'abc-123');

//     $this->assertSent('def-456', '{"event":"client-start-typing","channel":"test-channel","data":{"id":123,"name":"Joe Dixon"}}', 1);
// })->skip();

// it('can subscribe a connection to multiple channels', function () {
//     $this->subscribe('test-channel');
//     $this->subscribe('test-channel-2');
//     $this->subscribe('private-test-channel-3', data: ['foo' => 'bar']);
//     $this->subscribe('presence-test-channel-4', data: ['user_id' => 1, 'user_info' => ['name' => 'Test User 1']]);

//     expect(connectionManager()->all())->toHaveCount(1);
//     expect(channelManager()->all())->toHaveCount(4);

//     $connection = $this->managedConnection();

//     channelManager()->all()->each(function ($channel) use ($connection) {
//         expect(channelManager()->connectionKeys($channel))->toHaveCount(1);
//         expect(channelManager()->connectionKeys($channel)->map(fn ($conn, $index) => (string) $index))->toContain($connection->identifier());
//     })->skip();
// })->skip();

// it('can subscribe multiple connections to multiple channels', function () {
//     $this->subscribe('test-channel');
//     $this->subscribe('test-channel-2');
//     $this->subscribe('private-test-channel-3', data: ['foo' => 'bar']);
//     $this->subscribe('presence-test-channel-4', data: ['user_id' => 1, 'user_info' => ['name' => 'Test User 1']]);

//     $connection = $this->connect();
//     $this->subscribe('test-channel', connectionId: 'def-456');
//     $this->subscribe('private-test-channel-3', connectionId: 'def-456', data: ['foo' => 'bar']);

//     expect(connectionManager()->all())->toHaveCount(2);
//     expect(channelManager()->all())->toHaveCount(4);

//     expect(channelManager()->connectionKeys(ChannelBroker::create('test-channel')))->toHaveCount(2);
//     expect(channelManager()->connectionKeys(ChannelBroker::create('test-channel-2')))->toHaveCount(1);
//     expect(channelManager()->connectionKeys(ChannelBroker::create('private-test-channel-3')))->toHaveCount(2);
//     expect(channelManager()->connectionKeys(ChannelBroker::create('presence-test-channel-4')))->toHaveCount(1);
// })->skip();

// it('fails to subscribe to a private channel with invalid auth signature', function () {
//     $this->subscribe('private-test-channel', auth: 'invalid-signature');

//     $this->assertSent('abc-123', '{"event":"pusher:error","data":"{\"code\":4009,\"message\":\"Connection is unauthorized\"}"}');
// })->skip();

// it('fails to subscribe to a presence channel with invalid auth signature', function () {
//     $this->subscribe('presence-test-channel', auth: 'invalid-signature');

//     $this->assertSent('abc-123', '{"event":"pusher:error","data":"{\"code\":4009,\"message\":\"Connection is unauthorized\"}"}');
// })->skip();

// it('fails to connect when an invalid application is provided', function () {
//     App::make(Server::class)
//         ->handle(Request::fromLambdaEvent(
//             [
//                 'requestContext' => [
//                     'eventType' => 'CONNECT',
//                     'connectionId' => 'abc-123',
//                 ],
//                 'queryStringParameters' => [
//                     'appId' => 'invalid-app-id',
//                 ],
//             ]
//         ));

//     $this->assertSent('abc-123', '{"event":"pusher:error","data":"{\"code\":4001,\"message\":\"Application does not exist\"}"}');
// })->skip();

// it('cannot connect from an invalid origin', function () {
//     $this->app['config']->set('reverb.apps.apps.0.allowed_origins', ['https://laravel.com']);

//     App::make(Server::class)
//         ->handle(Request::fromLambdaEvent(
//             [
//                 'requestContext' => [
//                     'eventType' => 'CONNECT',
//                     'connectionId' => 'abc-123',
//                 ],
//                 'queryStringParameters' => [
//                     'appId' => 'pusher-key',
//                 ],
//             ]
//         ));

//     $this->assertSent('abc-123', '{"event":"pusher:error","data":"{\"code\":4009,\"message\":\"Origin not allowed\"}"}', 1);
// })->skip();

// it('can connect from a valid origin', function () {
//     $this->app['config']->set('reverb.apps.0.allowed_origins', ['laravel.com']);

//     App::make(Server::class)
//         ->handle(Request::fromLambdaEvent(
//             [
//                 'requestContext' => [
//                     'eventType' => 'CONNECT',
//                     'connectionId' => 'abc-123',
//                 ],
//                 'queryStringParameters' => [
//                     'appId' => 'pusher-key',
//                 ],
//                 'headers' => [
//                     'origin' => 'https://laravel.com',
//                 ],
//             ]
//         ));

//     $this->assertSent('abc-123', 'connection_established', 1);
// })->skip();

// it('clears application state between requests', function () {
//     $this->subscribe('test-channel');

//     expect($this->app->make(ConnectionManager::class)->app())->toBeNull();
//     expect($this->app->make(ChannelManager::class)->app())->toBeNull();
// })->skip();
