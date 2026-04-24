import os

def print_tree(path, prefix=""):
    files = os.listdir(path)
    for i, file in enumerate(files):
        full_path = os.path.join(path, file)
        connector = "└── " if i == len(files) - 1 else "├── "
        print(prefix + connector + file)
        if os.path.isdir(full_path):
            extension = "    " if i == len(files) - 1 else "│   "
            print_tree(full_path, prefix + extension)

print_tree(".")