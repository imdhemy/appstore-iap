.PHONY: build bash start

start: build bash

build:
	docker build -t imdhemy/appstore-iap .

bash:
	docker run --rm -it --name appstore-iap-container -v $(PWD):/var/www imdhemy/appstore-iap bash

%:
	@:
