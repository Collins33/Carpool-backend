import User from '../../models/user';

module.exports = {
    create(req,res){
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
    }
}
