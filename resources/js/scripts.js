const fs = require('fs');
const pdf = require('pdf-parse');
 
let dataBuffer = fs.readFileSync('http://localhost:8888/supercross/public/S1F1LINEUP.pdf');
 
pdf(dataBuffer).then(function(data) {
   console.log(data);
})
.catch(function(error){
    // handle exceptions
})