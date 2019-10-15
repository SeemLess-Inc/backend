import base64
import boto3
import json


BUCKET_NAME = 'elasticbeanstalk-eu-west-1-060643667111'


def put_file(file_name, file_content):
    s3 = boto3.client('s3')
    try:
        s3_response = s3.put_object(Bucket=BUCKET_NAME, Key=file_name, Body=file_content, ACL='public-read', ContentType='video/mp4')
    except Exception as e:
        raise IOError(e)


def lambda_handler(event, context):
    body = json.loads(event['body'])

    file_name = body['file_name']

    # https://stackoverflow.com/questions/32428950/converting-base64-to-an-image-incorrect-padding-error
    # data:video/mp4;base64,AAAAFGZ0eX...aVR1bkVYVEMAAAAQZGF0YQAAAAEAAAAA
    b64content = body['content'].split(",")[1]
    b64content += "=" * (4 - (len(b64content) % 4))
    b64content = b64content.encode()
    file_content = base64.decodebytes(b64content)

    put_file(file_name, file_content)

    response = {
        'file_name': file_name
    }

    return {
        'statusCode': 200,
        'body': json.dumps(response),
        'headers': {
            "Content-Type" : "application/json",
            "Access-Control-Allow-Origin" : "*",
            "Allow" : "GET, OPTIONS, POST",
            "Access-Control-Allow-Methods" : "GET, OPTIONS, POST",
            "Access-Control-Allow-Headers" : "*"
        }
    }


# Test event:
# {
#   "body": "{\r\n\"file_name\": \"test.mp4\",\r\n\"content\": \"c2FtcGxlIHRleHQ=\"}"
# }
