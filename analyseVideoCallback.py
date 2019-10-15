import base64
import boto3
import json


BUCKET_NAME = 'elasticbeanstalk-eu-west-1-060643667111'


def put_file(file_name, file_content):
    s3 = boto3.client('s3')
    try:
        s3_response = s3.put_object(Bucket=BUCKET_NAME, Key=file_name, Body=file_content, ACL='public-read')
    except Exception as e:
        raise IOError(e)


def lambda_handler(event, context):
    body = json.loads(event['body'])

    video_file_url = body['video_url']
    video_file_name = video_file_url.split("/")[-1]

    json_file_name = f'{video_file_name}.json'
    file_content = json.dumps(body, ensure_ascii=False)

    put_file(json_file_name, file_content)

    return {
        'statusCode': 200,
        'body': '{}'
    }


# Test event:
# {
#   "body": "{\r\n\"file_name\": \"test.mp4\",\r\n\"content\": \"c2FtcGxlIHRleHQ=\"}"
# }
