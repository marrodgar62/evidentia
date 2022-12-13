from locust import HttpUser, task, between

class loadTest(HttpUser):
	wait_time = between(1, 10)
	

	@task
	def load_index(self):
		self.client.get("/21/login")