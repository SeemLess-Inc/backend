import requests
import boto3
import urllib.parse
import json


CALLBACK_URL = 'https://ujxx6kt1f2.execute-api.eu-west-1.amazonaws.com/prod/analyse_callback'
NETRA_PROCESS_URL = 'http://video-api.getnetra.com/process_video'
BUCKET_NAME = 'elasticbeanstalk-eu-west-1-060643667111'


def analyse_video(file_name):
    s3_file_name = urllib.parse.quote_plus(file_name)
    s3_file_path = f'https://{BUCKET_NAME}.s3-eu-west-1.amazonaws.com/{s3_file_name}'

    # Pre-signed S3 file upload cannot set public permissions, so have to do it here
    response = boto3.resource('s3').ObjectAcl(BUCKET_NAME, s3_file_name).put(ACL='public-read')

    # Create blank default JSON response
    s3_json_file_name = f'{s3_file_name}.json'
    s3 = boto3.client('s3')
    try:
        s3_response = s3.put_object(Bucket=BUCKET_NAME, Key=s3_json_file_name, Body='{}', ACL='public-read')
    except Exception as e:
        raise IOError(e)

    # Call Netra
    headers = { 'Content-Type': 'application/json' }
    request = { 'video_url': s3_file_path, 'callback_url': CALLBACK_URL }

    response = requests.post(NETRA_PROCESS_URL,
                             headers=headers,
                             json=request)
    return response


def lambda_handler(event, context):
    body = json.loads(event['body'])

    video_file_name = body['file_name']
    response = analyse_video(video_file_name)

    status_code = response.status_code
    payload = json.dumps(response.text)

    return {
        'statusCode': status_code,
        'body': payload,
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
#   "body": "{\"file_name\": \"ApiTest.mp4\"}"
# }
