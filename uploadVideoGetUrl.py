import boto3
import uuid
import json


BUCKET_NAME = 'elasticbeanstalk-eu-west-1-060643667111'


def lambda_handler(event, context):
    s3 = boto3.client('s3')

    body = json.loads(event['body'])

    file_name = body['file_name']

    # Generate the presigned URL for put requests
    presigned_url = s3.generate_presigned_url(
        ClientMethod='put_object',
        Params={
            'Bucket': BUCKET_NAME,
            'Key': file_name,
            'ContentType': 'video/mp4'
        }
    )

    # Return the presigned URL
    response = {
        "upload_url": presigned_url
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
