import subprocess
import websockets
import asyncio

# Define the server URL and port
SERVER_URL = "ws://145.223.88.240:5031"
# Define the repository directory on the client side
GIT_REPO_DIR = './'

# Function to commit changes to the repository
def commit_changes():
    try:
        subprocess.check_call(['git', 'add', '.'], cwd=GIT_REPO_DIR)
        subprocess.check_call(['git', 'commit', '-m', 'Client commit'], cwd=GIT_REPO_DIR)
        subprocess.check_call(['git', 'push'], cwd=GIT_REPO_DIR)
        print("Committed and pushed changes to the repository")
    except subprocess.CalledProcessError as e:
        print(f"Error while committing and pushing changes: {e}")

# Function to send commit and push request to the server
async def send_commit_request():
    async with websockets.connect(SERVER_URL) as websocket:
        # Send the commit request to the server
        await websocket.send('commit_request')
        response = await websocket.recv()
        print(f"Received from server: {response}")

# Function to run the commit and push process
def handle_commit_push():
    commit_changes()
    asyncio.run(send_commit_request())

if __name__ == "__main__":
    handle_commit_push()
