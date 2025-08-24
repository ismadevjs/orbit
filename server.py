import os
import subprocess
from flask import Flask, request
from flask_socketio import SocketIO, emit

app = Flask(__name__)
socketio = SocketIO(app)

# Define the directory where the Git repository is located
GIT_REPO_DIR = '/path/to/your/git/repo'

# Function to pull changes from the repository
def pull_changes():
    try:
        subprocess.check_call(['git', 'pull'], cwd=GIT_REPO_DIR)
        print("Pulled latest changes from the repository")
    except subprocess.CalledProcessError as e:
        print(f"Error while pulling changes: {e}")

# SocketIO event to handle client calls
@socketio.on('pull_request')
def handle_pull_request(message):
    print('Received pull request from client.')
    pull_changes()
    emit('response', {'data': 'Pulled latest changes from repository'})

if __name__ == '__main__':
    socketio.run(app, host='0.0.0.0', port=5031)
