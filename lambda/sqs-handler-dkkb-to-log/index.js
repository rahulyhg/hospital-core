var AWS = require('aws-sdk');
const S3  = new AWS.S3();
exports.handler = (event, context, callback) => {
    event.Records.forEach( async ({ messageId, body, messageAttributes }) => {
        const messageBody = JSON.parse(body);
        // Handle Date
        const dateObj = new Date();
        const month = dateObj.getUTCMonth() + 1; //months from 1-12
        const day = dateObj.getUTCDate();
        const year = dateObj.getUTCFullYear();
        
        const newdate = year + "/" + month + "/" + day;
        const keyS3 = 'logs/dang-ky-kham-benh/user-input/' + messageAttributes.app_env.stringValue + '/' + newdate + '/' + messageBody.ho_va_ten + '.json';
        S3.putObject( {
            Bucket: messageAttributes.bucket.stringValue,
            Key: keyS3,
            Body: body
        })
        .promise()
        .then(() => {
            console.log( 'UPLOAD SUCCESS' ) 
            return true;
        })
        .catch( e => {
            console.error( 'ERROR', e );
            return false;
        });
    });
};