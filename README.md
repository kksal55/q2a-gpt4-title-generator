# GPT-4 Title Generator


The **GPT-4 Title Generator** is a plugin developed for the Question2Answer (Q2A) platform that automatically updates question titles using OpenAI's GPT-4 language model. The plugin generates meaningful and SEO-friendly titles based on the content provided by users, enhancing the quality of content on your platform.


## Table of Contents


- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)
- [Contact](#contact)


## Features


- Automatically generates titles based on user-submitted content
- Presents the generated title to the user for approval
- Allows users to edit the generated title
- Utilizes OpenAI's GPT-4 language model for high-quality title generation
- Enables activation or deactivation of the plugin from the admin panel
- If the GPT API fails to respond or times out, ensures the question is submitted with the original title


## Requirements


- **Question2Answer Version:** 1.8 or higher
- **PHP Version:** 7.4 or higher
- **PHP cURL Extension:** Must be enabled
- **OpenAI API Key:** A valid API key with access to the GPT-4 model is required


## Installation


1. **Download the Plugin:**


   Download this repository as a ZIP file or clone it using Git:


   ```bash
   git clone https://github.com/yourusername/gpt4-title-generator.git
   ```


2. **Upload the Plugin:**


   Upload the plugin files to the `qa-plugin` directory of your Q2A installation:


   ```
   qa-plugin/
   └── gpt4-title-generator/
       ├── qa-gpt4-title-generator.php
       ├── qa-gpt4-title-layer.php
       └── qa-plugin.php
   ```


3. **Define Your API Key:**


   Add the following line to the end of your `qa-gpt4-title-generator.php` file:


   ```php
   $api_key = 'YOUR_OPENAI_API_KEY';
   ```


   **Note:** Replace `'YOUR_OPENAI_API_KEY'` with your actual OpenAI API key. **Keep your API key secure and do not share it publicly.**


4. **Activate the Plugin:**


   Log in to your Q2A admin panel and navigate to the **Plugins** section. Find the **GPT-4 Title Generator Settings** plugin and activate it.


## Configuration


In the admin panel, go to the **Plugins** section and then to **GPT-4 Title Generator Settings**. Here, you can enable or disable the plugin.


## Usage


1. **Asking a Question:**


   Users can use the standard question submission form to ask their questions.


2. **Title Generation:**


   - When the user submits the form, the plugin sends the user's title and content to the GPT-4 API.
   - GPT-4 generates a meaningful and SEO-friendly title based on the user's content.


3. **User Approval:**


   - The generated new title is presented to the user in a modal dialog for approval.
   - The user can accept the title, edit it, or choose to use the original title.


4. **Publishing the Question:**


   - After the user approves or edits the title, the question is published with the updated title.
   - If no response is received from the GPT-4 API or an error occurs, the question is published with the original title.


## Testing


To test how the plugin behaves in different scenarios, follow these steps:


- **Invalidating the API Key:**


  Temporarily change your API key to an incorrect value in the `qa-config.php` file to simulate an API request failure.


  ```php
  $api_key = 'YOUR_OPENAI_API_KEY';
  ```


- **Introducing a Delay in the API Request:**


  Add an artificial delay in the `generate_title_with_gpt4` function in the `qa-gpt4-title-generator.php` file to simulate a timeout.


  ```php
  sleep(6); // Waits for 6 seconds
  ```


- **Reducing the Timeout Duration:**


  Decrease the timeout duration of the AJAX request in the `qa-gpt4-title-layer.php` file:


  ```javascript
  xhr.timeout = 2000; // 2 seconds
  ```


These tests will help you verify that the plugin publishes questions normally with the original title when the GPT API fails or times out.


## Contributing


Contributions are welcome! If you'd like to contribute to this project:


1. **Fork** the repository on GitHub.
2. **Clone** your fork locally:


   ```bash
   git clone https://github.com/yourusername/gpt4-title-generator.git
   ```


3. **Create a branch** for your feature or bug fix:


   ```bash
   git checkout -b feature/YourFeatureName
   ```


4. **Commit** your changes with a descriptive message:


   ```bash
   git commit -am 'Add a new feature: YourFeatureName'
   ```


5. **Push** your branch to your GitHub fork:


   ```bash
   git push origin feature/YourFeatureName
   ```


6. **Open a Pull Request** on the original repository.


## License


This project is licensed under the [MIT License](LICENSE). For more information, please see the [LICENSE](LICENSE) file.


## Contact



---


**Note:** This plugin uses OpenAI's GPT-4 language model. You must comply with [OpenAI's Terms of Use](https://openai.com/policies/terms-of-use) and policies. You are responsible for any costs incurred from using the API.


---


**Disclaimer:** This project is not affiliated with or endorsed by OpenAI.


---


*Please replace `[your-email@example.com]` with your actual email address.*
