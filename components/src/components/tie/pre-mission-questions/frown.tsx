import { Component, h, JSX, Prop, State } from "@stencil/core";
import { Mission } from "../../../model/TIE/mission";
import { PreMissionQuestions } from "../../../model/TIE/pre-mission-questions";

enum DisplayMode {
  Officer = "Officer",
  Secret = "Secret",
  Table = "Table"
}

@Component({
  tag: "pyrite-tie-pre-mission-questions",
  styleUrl: "frown.scss",
  shadow: true
})
export class PreMissionQuestionsComponent {
  @Prop() public mission: Mission;
  @State() private officer: PreMissionQuestions[];
  @State() private secret: PreMissionQuestions[];
  @State() private displayMode: DisplayMode = DisplayMode.Officer;
  @State() private selected: PreMissionQuestions;

  public componentWillLoad() {
    this.officer = this.mission.officerBriefing;
    this.secret = this.mission.secretBriefing;
  }

  public render(): JSX.Element {
    const modes = [DisplayMode.Officer];
    if (this.secret && this.secret.length) {
      modes.push(DisplayMode.Secret);
    }
    modes.push(DisplayMode.Table);

    return (
      <div class="wrapper">
        <div class="btn-group" role="group" aria-label="Pre Mission Question Mode Selector">
          {modes.map((mode) => (
            <button type="button" class="btn btn-info" onClick={this.modeSelect.bind(this, mode)}>
              {mode}
            </button>
          ))}
        </div>
        {this.renderMode()}
      </div>
    );
  }

  private modeSelect(mode: DisplayMode): void {
    this.displayMode = mode;
  }

  private renderMode(): JSX.Element {
    let questions = this.officer || [];
    switch (this.displayMode) {
      case DisplayMode.Table:
        questions = questions.concat(this.secret);
        return (
          <table class="table table-striped table-dark table-bordered">
            <thead class="thead-light">
              <tr>
                <th>Type</th>
                <th>Question</th>
                <th>Answer</th>
              </tr>
            </thead>
            <tbody>
              {questions.map((question) => (
                <tr>
                  <td class="type">{question.Type}</td>
                  <td class="question">{question.Question}</td>
                  <td class="answer">{question.Answer}</td>
                </tr>
              ))}
            </tbody>
          </table>
        );
      case DisplayMode.Secret:
        questions = this.secret;
      case DisplayMode.Officer:
        return (
          <div class={`image-wrapper tietext ${this.displayMode}`}>
            <div class="answer">{this.selected ? this.selected.Answer : ""}</div>
            <div class="questions">
              <ul>
                {questions.map((question: PreMissionQuestions) => {
                  return <li onClick={this.selectQuestion.bind(this, question)}>{question.Question}</li>;
                })}
                <li onClick={this.selectQuestion.bind(this, undefined)}>That is enough for now, sir.</li>
              </ul>
            </div>
          </div>
        );
    }
  }

  private selectQuestion(question: PreMissionQuestions): void {
    this.selected = question;
  }
}
