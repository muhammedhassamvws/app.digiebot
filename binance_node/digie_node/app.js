const express = require('express')
const app = express()
var router   = express.Router();
var bodyParser     = require('body-parser');
const conn = require('./database_connection')


var bodyParser = require('body-parser');
app.use(bodyParser.urlencoded({ extended: false }));
app.use(bodyParser.json());


app.post('/dashboard_api', function (req, res) {
	conn.then(db=> {
		let v = db.collection('market_depth_history').find({}).sort({"created_date": -1}).limit(20).toArray();
		v.then(vv=> {
			res.send(vv);
		});	
	})
});


app.get('/test', function (req, res) {
	conn.then(db=> {
		let v = db.collection('market_depth_history').find({}).sort({"created_date": -1}).limit(20).toArray();
		v.then(vv=> {
			res.send(vv);
		});	
	})
})




app.set('view engine', 'ejs');
app.listen(4512, function () {
  console.log('Example app listening on port 4512!')
})