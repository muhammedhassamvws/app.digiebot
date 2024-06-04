var MongoClient = require('mongodb').MongoClient;
var db;

function connectionDatabase() {

    return new Promise((resolve, reject) => {
        var url  = 'mongodb://binance:binance2019readwrite@localhost:27017/?authMechanism=SCRAM-SHA-1&authSource=binance';

            MongoClient.connect(url, { useNewUrlParser: true }, (err, client) => {
                if (err){
                    reject(err);
                }else{  
                const db = client.db('binance');
                // let data =  db.collection('market_depth_history').find({}).limit(10).toArray();
                // data.then(data_res => {
                //     console.log(data_res, "data_res")
                // })
                resolve(db)
                }//End of  connection success
            });//End of Db Connection
    })
}

module.exports = connectionDatabase()

