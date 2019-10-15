.PHONY: requirements requirements_test requirements_dev clean lint test build coverage

DIR = $(CURDIR)
TEST_PATH=./tests
REV=`git rev-parse --short HEAD 2> /dev/null | sed "s/\(.*\)/@\1/"`

requirements:
	pip install -r requirements/base.txt

requirements_test:
	pip install -r requirements/test.txt

requirements_tools:
	pip install -r requirements/tools.txt

requirements_dev:
	pip install -r requirements/dev.txt

pip_reset:
	pip freeze | xargs pip uninstall -y

clean:
	rm -rf build
	find . -type f -name '*.py[co]' -delete

build: clean
	mkdir -p build
	zip -r build/analyseVideo.zip . -x build/\* env/\* __pycache__/\* */\.* */\.pyc \.git/\* \.cache/\* &&\
	cd $(VIRTUAL_ENV)/lib/python3.6/site-packages/ &&\
	zip -ur $(DIR)/build/analyseVideo.zip . -x build/\* env/\* __pycache__/\* */\.* */\.pyc \.git/\* \.cache/\*

lint: requirements_tools
	flake8 --exclude=env

coverage: requirements_tools
	pytest --cov=analyseVideo $(TEST_PATH)
	coverage xml

test: requirements_test
	python -m pytest -s $(TEST_PATH)
