const express = require('express')
const logger = require('morgan')
const bodyParser = require('body-parser')

// setup the express app
const app = express()

//set up the port to listen from
const port = 3000

// log requests to the console
app.use(logger('dev'))

// parse incoming request data
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({extended: false}))

require('./routes/index')(app);
// set up catch all endpoint
app.get('*', (req,res) => res.status(200).send({
    message:'Welcome to carpool'
}))
app.listen(port, ()=> console.log(`listening to port ${port}`))

module.exports = app;
