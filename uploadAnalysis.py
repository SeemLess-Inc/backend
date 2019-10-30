import base64
import boto3
import json


BUCKET_NAME = 'elasticbeanstalk-eu-west-1-060643667111'


def put_file(file_name, file_content):
    s3 = boto3.client('s3')
    try:
        s3_response = s3.put_object(Bucket=BUCKET_NAME, Key=file_name, Body=bytes(json.dumps(file_content).encode('UTF-8')), ACL='public-read', ContentType='application/json')
    except Exception as e:
        raise IOError(e)


def lambda_handler(event, context):
    body = json.loads(event['body'])

    file_name = body['file_name']
    file_content = body['content']

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
#   "body": "{\r\n\"file_name\": \"test.json\",\r\n\"content\": \"c2FtcGxlIHRleHQ=\"}"
# }
