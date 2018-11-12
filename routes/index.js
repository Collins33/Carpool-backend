const UserController = require('../controllers/users/UserController')

module.exports =(app)=>{
    app.get('/api/v1', (req,res) => res.status(200).send({
        message:'Welcome to carpool backend'
    }));

    
    app.post('/api/v1/user', UserController.make);
    app.get('/api/v1/user', UserController.show);
}