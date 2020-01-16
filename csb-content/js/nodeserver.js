var server = require('http').Server();

var io = require('socket.io')(server);

var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('newdiscussion', 'newreply', 'favorited');

redis.on('message', (channel, message) => {
    message = JSON.parse(message);

    console.log(channel, message);

    switch (channel) {
        case 'newdiscussion':
            io.emit(`${channel}:all`, message.data);
            console.log(`${channel}:all`);
            io.emit(`${channel}:${message.data.discussion.category.name}`, message.data);
            console.log(`${channel}:${message.data.discussion.category.name}`);
            io.emit(`${channel}:${message.data.discussion.user.name}`, message.data);
            console.log(`${channel}:${message.data.discussion.user.name}`);
            break;
        case 'newreply':
            io.emit(`${channel}:discussion:${message.data.discussion.title}`, message.data);
            console.log(`${channel}:discussion:${message.data.discussion.title}`);
            io.emit(`${channel}:user:${message.data.reply.user.name}`, message.data);
            console.log(`${channel}:user:${message.data.reply.user.name}`);
            break;
        case 'favorited':
            io.emit(`${channel}:${message.data.favorable_type}:${message.data.favorable.id}`, message.data);
            console.log(`${channel}:${message.data.favorable_type}:${message.data.favorable.id}`);
            io.emit(`${channel}:user:${message.data.user.name}`, message.data);
            console.log(`${channel}:user:${message.data.user.name}`);
            break;
        default:
            io.emit(`${channel}:${message.event}`, message.data);
    }
});

server.listen(3000, () => {
    console.log('Listening on port 3000');
});
