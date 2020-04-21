var app = require('express')();
var server = require('http').Server(app);
var io = require('socket.io')(server);
var request = require('request');
var cors = require('cors');

app.use(cors());

var port = 8890;
var connectedUsers = {};
var baseUrl = 'http://localhost/FoodCart_web';
 server.listen(port, function () {
    console.log('listening on *:' + port);
});

io.on('connection', function (socket) {
    socket.on('register', function (packet) {
        var userJID = packet.userID
        // console.log(userJID);
        if (connectedUsers.hasOwnProperty(userJID)) {
            connectedUsers[userJID].removeAllListeners();
            connectedUsers[userJID].disconnect();
            delete connectedUsers[userJID];
        }
        if (!connectedUsers.hasOwnProperty(userJID)) {
            socket.userId = userJID;
            connectedUsers[userJID] = socket;
        }
    });

    socket.on('chat', function (packet) {
        var toUser = packet.toUser;
        var sender_id = packet.fromUser;
        var message = packet.text;
        var jobId = packet.jobId;
        var threadId = packet.threadId;
        var timeZone = packet.timeZone;
        if (connectedUsers.hasOwnProperty(sender_id)) {
            var options = {
                url: baseUrl + '/save-chat-message',
                method: 'POST',
                dataType: "JSON",
                body: JSON.stringify({ "toUser": toUser, "sender_id": sender_id, "message": message, "job_id": jobId, "thread_id": threadId ,'timeZone':timeZone}),
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            request(options, function (error, response, body) {
                var newResponse = JSON.parse(response.body);
                connectedUsers[sender_id].emit('single-chat-box', { data: newResponse.fromBody, fromUser: sender_id });
                if (connectedUsers.hasOwnProperty(toUser)) {
                    connectedUsers[toUser].emit('single-chat-box', { data: newResponse.toBody, fromUser: sender_id });
                }
            });
        }

    });
    socket.on('chatAttachment', function (packet) {
        var toUser = packet.toUser;
        var sender_id = packet.fromUser;
        var doc = packet.file;
        var info = packet.fileInfo;
        var jobId = packet.jobId;
        var threadId = packet.threadId;
        var timeZone = packet.timeZone;
        if (connectedUsers.hasOwnProperty(sender_id)) {
            var options = {
                url: baseUrl + '/send-chat-doc',
                method: 'POST',
                dataType: "JSON",
                body: JSON.stringify({ "toUser": toUser, "sender_id": sender_id, "file": doc, fileInfo: info, "job_id": jobId, "thread_id": threadId ,'timeZone':timeZone}),
                headers: {
                    'Content-Type': 'application/json'
                }
            };
            request(options, function (error, response, body) {
                var newResponse = JSON.parse(response.body);
                connectedUsers[sender_id].emit('single-chat-box', { data: newResponse.fromBody, fromUser: sender_id });
                if (connectedUsers.hasOwnProperty(toUser)) {
                    connectedUsers[toUser].emit('single-chat-box', { data: newResponse.toBody, fromUser: sender_id });
                }
            });
        }

    });

    socket.on('chatlist', function (packet) {
        var toUser = packet.toUser;
        var fromUser = packet.fromUser;
        var timeZone = packet.timeZone;

        if (connectedUsers.hasOwnProperty(fromUser)) {
            var options = {
                url: baseUrl + '/get-chat-list',
                method: 'POST',
                body: JSON.stringify({ "toUser": toUser, "fromUser": fromUser,'timeZone':timeZone }),
                headers: {
                    'Content-Type': 'application/json'
                }
            };

            request(options, function (error, response, body) {
                connectedUsers[fromUser].emit('chat-list', { data: body, fromUser: fromUser });
            });
        }
    });
    socket.on('unreadCount', function (packet) {
       var fromUser = packet.fromUser;
       if (connectedUsers.hasOwnProperty(fromUser)) {
            var options = {
                url: baseUrl + '/unread-message',
                method: 'POST',
                body: JSON.stringify({"fromUser": fromUser }),
                headers: {
                    'Content-Type': 'application/json'
                }
            };

            request(options, function (error, response, body) {
                // console.log('response ',response);
                // console.log('response.body ',response.body);
                var newResponse = JSON.parse(response.body);
                connectedUsers[fromUser].emit('unread-count', { data: newResponse, fromUser: fromUser });
            });
        }
    });

    socket.on('set-online', function (packet) {
       var userId = packet.userId
       socket.broadcast.emit('show-online', {'userId': userId})
    });

    socket.on('set-offline', function (packet) {
        var userId = packet.userId
        socket.broadcast.emit('show-offline', {'userId': userId})
    });
    socket.on('typing', function (packet) {
        var toUser = packet.to;
        if (connectedUsers.hasOwnProperty(toUser)) {
            connectedUsers[toUser].emit('typing', packet);
        }
    });
    socket.on('disconnect', function () {
            if (connectedUsers.hasOwnProperty(socket.userId)) {
                socket.broadcast.emit('show-offline', {'userId': socket.userId})
                delete connectedUsers[socket.userId];

                socket.removeAllListeners();
                socket.leave();
            }
            ;
        });
});
