var express = require('express');
var https = require('https');
var http = require('http');
var fs = require('fs');

var config = require('./serverconfig');

var privateKey = fs.readFileSync(config.privateKey, 'utf8');
var certificate = fs.readFileSync(config.certificate, 'utf8');

// This line is from the Node.js HTTPS documentation.
var options = {
    key: privateKey,
    cert: certificate
};
var app = express();


var server = https.createServer(options, app);
var io = require('socket.io')(server);

var Redis = require('ioredis');
var redis = new Redis();


redis.subscribe('newdiscussion', 'newreply', 'favorited', 'notification');

io.on('connection', (socket) = > {
    console.log("Connection detected");
})
;

redis.on('message', (channel, message) = > {
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
    case 'notification':
        io.emit(`${channel}:${message.data.notification.user.name}`, message.data);
        console.log(`${channel}:${message.data.notification.user.name}`);
        break;
    case 'globalnotification':
        io.emit(`${channel}`, message.data);
        console.log(`${channel}`);
        break;
    default:
        io.emit(`${channel}`, message.data);
        console.log(`${channel}`);
}
})
;

server.listen(config.port, config.host, () = > {
    console.log("Listening on port 3000")
    console.log(server.address())
}
)
;
