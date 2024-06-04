const express = require('express')
const app = express()
var router   = express.Router();
var bodyParser     = require('body-parser');
const conn = require('././database_connection');
var request = require('request');

/* GET home page. */

app.post('/btc_usd_ticker', function (req, res){
	request.get('https://blockchain.info/ticker',function(err,res,body){
	  if(err) //TODO: handle err
	  if(res.statusCode === 200 ){
	  	console.log(res, "res")
	  } //etc
	  //TODO Do something with response
	});
})

app.post('/dashboard_api', function (req, res) {
	conn.then(db=> {
		let v = db.collection('market_depth_history').find({}).sort({"created_date": -1}).limit(20).toArray();
		v.then(vv=> {
			res.send(vv);
		});	
	})
})


module.exports = router;
