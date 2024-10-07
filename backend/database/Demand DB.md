# Demand DB

An extremely simple JSON-only database. It is NOT designed to be efficient, BUT it is designed to run only within the PHP scripts and not need its own runtime.

## Design

1. Data is stored in collections
3. Collections stored as objects, with documents as properties of the object. Collection\[DocumentID\] = Document
4. Documents can be accessed directly or multiple at a time