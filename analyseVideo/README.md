# analyzeVideo
A lambda to call the Netra API to analyse a video

## make targets
1. `make build` gives you a `.zip` of your `python` code and dependencies in the `./build/` directory.
    - remember to `make requirements` first so that all the dependencies are bundelled properly
    - on initial deploy you'll need to `make build` so that you can upload the ZIP to the AWS lambda console.
