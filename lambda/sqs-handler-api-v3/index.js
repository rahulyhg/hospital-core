const axios = require('axios')
const appUri = process.env.API_URI;
const appUrl = process.env.API_URL;

console.log('Loading SQS Handler function!!!+++');

exports.handler = (event, context, callback) => {
    //console.log('Received event:', JSON.stringify(event, null, 2));
    event.Records.forEach( async ({ messageId, body, messageAttributes }) => {
        //console.log('SQS messageAttributes %s: %j', messageId, messageAttributes);
        //console.log('SQS message %s: %j', messageId, body);
        console.log('SQS message %s: %j', messageId, JSON.parse(body));
    
        let res = await axios.post(appUri+'dontiep/hsbaKp/cache/fromQueue',{
          message_id: messageId,
          message_attributes:JSON.stringify(messageAttributes),
          message_body:JSON.stringify(JSON.parse(body))
        })
        .then(response => {
          console.log('added to cache success');
          return true;
        })
        .catch((error) => {
            // Error
            console.log('got error?');
            if (error.response) {
                // The request was made and the server responded with a status code
                // that falls out of the range of 2xx
                // console.log(error.response);
                // console.log(error.response.status);
                 console.log(error.response.status);
                 
                if (error.response.status == 404){
                    return false;
                }
                if (error.response.status == 422){
                    return false;
                }
            } else if (error.request) {
                // The request was made but no response was received
                // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                // http.ClientRequest in node.js
                console.log('The request was made but no response was received');
                return false;
            } else {
                // Something happened in setting up the request that triggered an Error
                console.log('Something happened in setting up the request that triggered an Error');
                return false;
            }
            console.log('other');
            return false;
        });
        return res;
        
    });
    return `Successfully processed ${event.Records.length} messages.`;
};