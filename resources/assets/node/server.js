/**
 * Created by lenovo on 2017/01/26.
 */
const app = require('http').createServer(handler);
const fs = require('fs');
const io = require('socket.io')(app);

const Redis = require('ioredis');
const _redis = {
  host: '119.29.244.34',
  port: '6379',
  password: 'chenjunwu007',
  db: 1
};
const redis = new Redis(_redis);

app.listen(6001, () => {
  console.log('Server is running!');
  // console.log(__dirname);
});

function handler(req, res) {
  fs.readFile(`${__dirname}/welcome.html`, (err, data) => {
    res.writeHead(200, {'Content-Type': 'text/html'});
    res.write(data.toString());
    res.end();
  });
}

io.on('connection', function(socket) {
  console.log('connected');
});

redis.psubscribe('*', function(err, count) {
  // console.log(count);
});

redis.on('pmessage', function(subscribed, channel, message) {
  message = JSON.parse(message);
  console.log(channel);
  console.log(message);
  // io.emit(channel + ':' + message.event, message.data);
  io.emit(`${channel}:${message.event}`, message.data);
});
