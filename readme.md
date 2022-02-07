From within this directory:

- Get your enviroment up and running \
`docker-compose up -d`

- Run composer install \
`docker-compose exec api composer install`

- Run database migrations \
`docker-compose exec api php vendor/bin/phinx migrate`

- Copy and paste the google auth json file in the root folder

I have also created a postman collection for you to make testing easy

Good morning Car Track team 
 
I am happy to present you with my development test. 
 
I created a twitter clone API that does natural language processing on a tweet so in future a trend engine could be built 
 
The reason I decided to do this is so I could have a many-many table relationship and it sounded fun at the time. 
 
Also I made a big mess up with my NLP terminologies. I confused sentiments and entities and only realized this after plugin in the Language Service and was not in a mood to refactor everything out 
 
The Main library I used is League\Router and on their site they don't consider it a framework :) 
 
I also used phinx for my migrations and the reason for this was so I could set up my TDD environment. I wanted to test my Repos against the DB. 
 
I wanted to add basic auth to the API and make use of a DI framework but then ran out of time. 
 
The docker container contains a very special php extension for the google NPL library so I'm not too sure if it would run in your test environment 
 
If possible I would like to chat with you about my code because I made some very strange decisions but they all had a good reason 
 
If you have a problem with installing the Api, I am more than happy to make you a video and walk you through the code and the API 

 

Final note.. I did not create any indexes on my db other than the auto increments. I know I needed a composite unique key on tweet_id and on sentiment_id on the senitiments table and could have added a foreign key constraint 

And I could have also added an index on the tweet's created_at on because it is used when searching.  
The biggest Db that I have worked with is 13tb in size and it is a mysql install and have experience in query optimization and know how to debug a slow query 