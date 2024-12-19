function CreateAllMusicsCard(audioName, audioSrcUrl, audioSrcType, picFoldersName, picSrcUrl) {
    audios = new Map();
    parent = document.getElementById("musicsList").firstElementChild;
    i = 0;
    id = "";
    audioName.forEach((element) => {
      audiosSrc = [];
      picsSrc = [];
      audioSrcType.forEach((type) => {
        audiosSrc.push(audioSrcUrl + element[0] + "." + type);
      });
      picFoldersName.forEach((folderName) => {
        picsSrc.push(picSrcUrl + folderName + "\\" + element[2] + ".jpg");
      });
      id = "Music" + i.toString();
      CreateMusicCard(id,parent,element[0], element[1], picsSrc);
      audios.set(id,audiosSrc);
      i++;
    });
    return audios;
  }

  function CreateMusicCard(id, parent, title, artist, picsSrc) {
    title = title.replaceAll("_"," ");
    artist = artist.replaceAll("_"," ");
    row = document.getElementById("musicsList").firstElementChild;
    divParent = document.createElement("div");
    divParent.classList.add("col-6", "col-md-4", "col-lg-3");
    card = document.createElement("div");
    card.classList.add("card");
    card.setAttribute('id',id.toString());
    card.setAttribute('onclick', "NewMusic('"+ id.toString() +"')");
    card.setAttribute('data-title', title);
    card.setAttribute('data-artist', artist);
    createPicture(card, picsSrc);
    cardBody = document.createElement("div");
    titleText = document.createElement("h5");
    titleText.classList.add("card-title", "text-primary");
    titleText.setAttribute("style", "text-align: center");
    titleText.innerText = MaxChar(title, 12);
    card.appendChild(titleText);
    paragraph = document.createElement("p");
    paragraph.classList.add("card-title", "text-primary");
    paragraph.setAttribute("style", "text-align: center");
    paragraph.innerText = MaxChar(artist, 12);
    card.appendChild(paragraph);
    divParent.appendChild(card);
    parent.appendChild(divParent);
  }

  function MaxChar(string, maxLength){

    if(string.length > maxLength){
      string = string.substr(0,maxLength - 3) + "...";
    }

    return string;
  }

  function createPicture(parent, picsSrc) {
    gridSize = [0, 576, 768, 992];
    picture = document.createElement("picture");
    for (let i = 1; i < picsSrc.length; i++) {
      source = document.createElement("source");
      source.setAttribute("media", "(min-width:" + gridSize[i] + "px)");
      source.setAttribute("srcset", picsSrc[i]);
      picture.appendChild(source);
    }
    img = document.createElement("img");
    img.classList.add("card-img-top", "pt-3", "px-2");
    img.setAttribute("src", picsSrc[0]);
    img.setAttribute("alt", "albumPic");
    img.setAttribute("style", "width:100%");

    picture.appendChild(img);
    parent.appendChild(picture);
  }

  function Clamp(x,a,b){
    if(x < a){
      return a;
    }

    if(x > b){
      return b;
    }

    return x;
  }
