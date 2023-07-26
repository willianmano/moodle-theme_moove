import './App.css';
import logo from './logo.svg';
import MoodleWs from "./services/moodlews";

function App(props) {
    let strings = [];
    let moodleWsInstance = new MoodleWs(props.sesskey, props.wwwroot);
    let stringidentifier = { strings: [{stringid: 'activityattachments', component: 'mod_assign'}]};
    let strignsWS = moodleWsInstance.getMoodleStrings(stringidentifier);
    if (strignsWS) {
        strings = strignsWS;
    }
  return (
    <div className="App">
      <header className="App-header">
        <img class="App-logo" src={logo} />
        <p>This is a test of a react with props from Moodle to React.</p>
        <p>Username: {props.username} {props.lastname}</p>
        <p>and this test the WS class:</p>
        <p>Strings call:</p>
        <p>{strings[0].string}</p>
      </header>
    </div>
  );
}

export default App;
