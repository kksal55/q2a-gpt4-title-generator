# GPT-4 Title Generator


The **GPT-4 Title Generator** is a plugin for the Question2Answer (Q2A) platform that automatically updates question titles using OpenAI's GPT-4 language model. It generates meaningful and SEO-friendly titles based on user-submitted content.


## Installation


To install the GPT-4 Title Generator plugin, follow these steps:


1. Download or clone the repository:


   ```bash
   git clone https://github.com/yourusername/gpt4-title-generator.git
   ```


2. Upload the plugin to your Q2A `qa-plugin` directory:


   ```
   qa-plugin/
   └── gpt4-title-generator/
       ├── qa-gpt4-title-generator.php
       ├── qa-gpt4-title-layer.php
       └── qa-plugin.php
   ```


3. Add your OpenAI API key to the `qa-gpt4-title-generator.php` file:


   ```php
   $api_key = 'YOUR_OPENAI_API_KEY';
   ```


4. Activate the plugin from the Q2A admin panel in the **Plugins** section.


## Configuration


In the Q2A admin panel, navigate to the **Plugins** section and select **GPT-4 Title Generator Settings**. Here, you can enable or disable the plugin.


## Usage


Once installed and activated, the GPT-4 Title Generator works as follows:


1. **Question Submission:**
   - When users submit a question, the plugin sends the content and title to the GPT-4 API.
   - GPT-4 generates an improved and SEO-optimized title.


2. **User Approval:**
   - The generated title is shown to the user in a modal dialog.
   - The user can either accept the title, edit it, or keep their original title.


3. **Publishing:**
   - After approval, the question is published with the selected title.
   - If the GPT-4 API times out or fails, the original title will be used.


## Testing


To simulate different scenarios, you can:


1. **Invalid API Key:**
   Change the API key in `qa-config.php` to an invalid value to test how the plugin behaves when the API fails.


   ```php
   $api_key = 'YOUR_OPENAI_API_KEY';
   ```


2. **API Delay:**
   Add a delay in the API request to simulate a slow response:


   ```php
   sleep(6); // 6-second delay
   ```


3. **Timeout Setting:**
   Adjust the AJAX timeout in `qa-gpt4-title-layer.php` to force a timeout:


   ```javascript
   xhr.timeout = 2000; // 2-second timeout
   ```


## Contributing


Pull requests are welcome. For major changes, please open an issue first to discuss the changes you propose.


Make sure to update tests as appropriate.


## License


[MIT](https://choosealicense.com/licenses/mit/)


---


Feel free to replace `[kksal55]` with your actual GitHub username and provide any additional notes as needed.
