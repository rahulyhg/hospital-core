var AWS = require('aws-sdk');
const S3  = new AWS.S3();
exports.handler = (event, context, callback) => {
    event.Records.forEach( async ({ messageId, body, messageAttributes }) => {
        const messageBody = JSON.parse(body);
        S3.putObject( {
            Bucket: 'tien-trinh-dang-ky-benh-nhan',
            Key: messageBody.ho_va_ten + '.json',
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