class AudioManager {
    currentMusicId = "Music0";
    index = 0;
    playlist;

  constructor(playlist){
    this.playlist = playlist;
  }

  get GetCurrentIndex(){
    return this.index;
  }

  get GetCurrentAudioScr(){
    return this.playlist.get(this.currentMusicId);
  }

  get GetCurrentMusicId(){
    return this.currentMusicId;
  }

  ChangeMusicId(id){
    this.currentMusicId = id;
    this.index = parseInt(this.currentMusicId.substring(5));
    return this.GetCurrentAudioScr;
  }
  
  ChangeMusicIndex(index){
    this.ChangeMusicId("Music" + ((index + this.playlist.size) % this.playlist.size).toString());
    return this.GetCurrentAudioScr;
  }

  NextMusic(){
    this.ChangeMusicIndex(this.index + 1);
    return this.GetCurrentAudioScr;
  }

  PreviousMusic(){
    this.ChangeMusicIndex(this.index - 1);
    return this.GetCurrentAudioScr;
  }
}
