start:
	docker build -t imdhemy/liap .

run:
	docker run -it --rm -d -v $(shell pwd):/var/www imdhemy/liap

%:
	@:
