import boto3
import json


BUCKET_NAME = 'elasticbeanstalk-eu-west-1-060643667111'


def get_file(file_name):
    s3 = boto3.client('s3')
    try:
        s3_response = s3.get_object(Bucket=BUCKET_NAME, Key=file_name)
    except Exception as e:
        raise IOError(e)

    file_content = json.loads(s3_response['Body'].read().decode('utf-8'))
    return file_content


def lambda_handler(event, context):
    video_file_name = event['id']
    json_file_name = f'{video_file_name}.json'

    analytics = get_file(json_file_name)

    return {
        'statusCode': 200,
        'body': analytics
    }


# Test event:
# {
#   "id": "ApiTest.mp4"
# }

# Test response:
# {
#   "statusCode": 200,
#   "body": {
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.543600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.543600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6ggv"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.1083600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.1083600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6h9d"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.3600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.3600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6hnq"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.273600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.273600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6i2n"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.93600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.93600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6ihm"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.633600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.633600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6iwu"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.363600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.363600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6jd6"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.1263600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.1263600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6jsb"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.1353600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.1353600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6k7g"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.903600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.903600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6kmi"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.1173600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.1173600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6l18"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.813600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.813600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6lfl"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.183600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.183600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6lu2"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.993600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.993600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6m8l"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.453600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.453600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6mn0"
#     },
#     "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.723600.jpg": {
#       "message": "Image successfully added to processing queue, results will be sent to callback_url shortly.",
#       "image_url": "https://storage.googleapis.com/netra_video_processing/videos/video_name_1568235917.1208997/image.723600.jpg",
#       "callback_url": "http://116.203.177.63:80",
#       "request_id": "aa4uwhmk0fr6n1e"
#     }
#   }
# }
