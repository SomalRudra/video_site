function showOptions() {
    var options = document.getElementById("options");
    options.style.display = options.style.display === "none" ? "block" : "none";
  }

  function updateTextbox() {
    var textbox = document.querySelector('.textbox');
    var button = document.querySelector('.upload-button');
    options.style.display = 'none';
    if (button) {
      button.style.display = 'none';
    }

    var selectedOption = document.querySelector('input[name="option"]:checked').value;
    if (selectedOption === 'markdown') {
      formatAsCodeBlock(textbox)
    }else{
      textbox.value = '';
    }
  }

  function formatAsCodeBlock(textbox) {
    textbox.value = '```' + "\n"+textbox.value + "\n"+ '```';
  }

  function updateTextboxButton() {
    options.style.display = 'none';
    var textbox = document.querySelector('.textbox');
    textbox.placeHolder = 'Click on the upload image button to upload an image'
    textbox.value = '';
  
    var button = document.querySelector('.upload-button');
    
    if (button) {
      // Button already exists, update text content
      button.textContent = 'Upload Image';
      button.style.display = 'block'
    } else {
      // Button doesn't exist, create new button
      button = document.createElement('button');
      button.textContent = 'Upload Image';
      button.classList.add('button-style');
      button.classList.add('upload-button');
      textbox.parentNode.insertBefore(button, textbox.nextSibling);
    }
    
   
  }
  
  
  