===========
Gets all the posts and their tags if they have any
TODO: figure out how to incorporate this into the home page loop
===========

SELECT posts.title, tags.name
FROM posts
LEFT JOIN post_tags
	ON posts.post_id = post_tags.post_id
LEFT JOIN tags 
	ON tags.tag_id = post_tags.tag_id
WHERE posts.is_published = 1