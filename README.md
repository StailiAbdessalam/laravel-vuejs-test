# A Simple blog Test

To start the simple blog test you have to fork the repo, and work your own GitHub 

## Requirements
- Laravel
- Vue 3 (composition api)
- Tailwind
- Inertia
- UI/UX (as you prefer)

#### Backend
- Create fake different users with role and permissions[read, edit, create] (you use Spatie permissions)
    - 1 user admin(Ader solution): have all permissions 
    - Other users : have read 
- Create migrations for categories with fake data (id, name, image)
- Create migrations articles ( id ,name, has one category, image, description)
- Article unit test

#### Frontend
- Article crud (inertia)
- Article should have categories from api and users from global inertia data (all field are required)
- List articles with filters:
    - Search by name and description
    - Category
    - User
- Apply the user permissions 



The deadline is 7 days