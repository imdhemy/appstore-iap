start:
	docker build -t imdhemy/liap .

bash:
	docker run -it --rm -v $(shell pwd):/app:cached imdhemy/liap bash

%:
	@:
