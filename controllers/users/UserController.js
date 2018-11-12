const User = require('../../models').User

module.exports = {
    make(req,res){
        return User
            .create({
                name: req.body.name,
                email: req.body.email
            })
            .then(
                user => res.status(201).send(user)
            )
            .catch(
                error => res.status(400).send(error)
            )
    },

    
    show(req,res){
        return User
            .all()
            .then(
                user => res.status(200).send(user)
            )
            .catch(
                error => res.status(400).send(error)
            )

    }
}
