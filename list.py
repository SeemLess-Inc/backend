import boto3
from botocore.exceptions import ClientError
import json


BUCKET_NAME = 'elasticbeanstalk-eu-west-1-060643667111'
BUCKET_URL = "http://" + BUCKET_NAME + ".s3-eu-west-1.amazonaws.com"
API_URL = "https://ujxx6kt1f2.execute-api.eu-west-1.amazonaws.com/prod/get_analytics"


def list_analysed_files():
    s3 = boto3.client('s3')
    files = []
    try:
        for key in s3.list_objects(Bucket=BUCKET_NAME)['Contents']:
            filename = key['Key']
            if filename.endswith('.mp4'):
                json_filename = filename + ".json"
                try:
                    timestamp = key['LastModified'].isoformat()
                    # obj = s3.head_object(Bucket=BUCKET_NAME, Key=json_filename)
                    analytics = API_URL + "/" + json_filename
                except ClientError as e:
                    #if e.response['Error']['Code'] != '404':
                    timestamp = ""
                    analytics = ""

                blob = {
                    "id": filename,
                    "title": filename,
                    "src": BUCKET_URL + "/" + filename,
                    "thumbnail": "",
                    "analytics": analytics,
                    "uploadedDate": timestamp,
                    "duration": ""
                }
                files.append(blob)
    except Exception as e:
        raise IOError(e)

    return files


def lambda_handler(event, context):
    analysed_files = list_analysed_files()
    response = json.dumps(analysed_files)

    return {
        'statusCode': 200,
        'body': response
    }


# Test event:
# {
# }

# Test response:
# {
#   "statusCode": 200,
#   "body": "[\"ApiTest.mp4\", \"ApiTest2.mp4\"]"
# }
