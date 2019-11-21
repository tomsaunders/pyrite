/* tslint:disable */
/**
 * This is an autogenerated file created by the Stencil compiler.
 * It contains typing information for all components that exist in this project.
 */


import { HTMLStencilElement, JSXBase } from '@stencil/core/internal';
import {
  FlightGroup,
  Mission,
} from './model/TIE';
import {
  Mission as Mission1,
} from './model/TIE/mission';

export namespace Components {
  interface AppHome {}
  interface AppRoot {}
  interface PyriteMission {
    'file': string;
    'name': string;
    'nr': string;
    'plt': string;
    'sub': string;
  }
  interface PyriteMissionTabs {}
  interface PyriteResource {
    'name': string;
  }
  interface PyriteResources {}
  interface PyriteScratch {}
  interface PyriteTieBriefing {
    'mission': Mission;
  }
  interface PyriteTieFlightgroup {
    'flightGroup': FlightGroup;
  }
  interface PyriteTieFlightgroups {
    'mission': Mission;
  }
  interface PyriteTiePlt {
    'file': string;
  }
  interface PyriteTiePreMissionQuestions {
    'mission': Mission;
  }
  interface PyriteTieScore {
    'mission': Mission;
  }
  interface PyriteXvtPlt {
    'ehtc': boolean;
    'file': string;
  }
  interface TacBattle {
    'nr': string;
    'plt': string;
    'sub': string;
  }
  interface TacBattleTypes {}
  interface TacBattles {
    'plt': string;
    'sub': string;
  }
}

declare global {


  interface HTMLAppHomeElement extends Components.AppHome, HTMLStencilElement {}
  var HTMLAppHomeElement: {
    prototype: HTMLAppHomeElement;
    new (): HTMLAppHomeElement;
  };

  interface HTMLAppRootElement extends Components.AppRoot, HTMLStencilElement {}
  var HTMLAppRootElement: {
    prototype: HTMLAppRootElement;
    new (): HTMLAppRootElement;
  };

  interface HTMLPyriteMissionElement extends Components.PyriteMission, HTMLStencilElement {}
  var HTMLPyriteMissionElement: {
    prototype: HTMLPyriteMissionElement;
    new (): HTMLPyriteMissionElement;
  };

  interface HTMLPyriteMissionTabsElement extends Components.PyriteMissionTabs, HTMLStencilElement {}
  var HTMLPyriteMissionTabsElement: {
    prototype: HTMLPyriteMissionTabsElement;
    new (): HTMLPyriteMissionTabsElement;
  };

  interface HTMLPyriteResourceElement extends Components.PyriteResource, HTMLStencilElement {}
  var HTMLPyriteResourceElement: {
    prototype: HTMLPyriteResourceElement;
    new (): HTMLPyriteResourceElement;
  };

  interface HTMLPyriteResourcesElement extends Components.PyriteResources, HTMLStencilElement {}
  var HTMLPyriteResourcesElement: {
    prototype: HTMLPyriteResourcesElement;
    new (): HTMLPyriteResourcesElement;
  };

  interface HTMLPyriteScratchElement extends Components.PyriteScratch, HTMLStencilElement {}
  var HTMLPyriteScratchElement: {
    prototype: HTMLPyriteScratchElement;
    new (): HTMLPyriteScratchElement;
  };

  interface HTMLPyriteTieBriefingElement extends Components.PyriteTieBriefing, HTMLStencilElement {}
  var HTMLPyriteTieBriefingElement: {
    prototype: HTMLPyriteTieBriefingElement;
    new (): HTMLPyriteTieBriefingElement;
  };

  interface HTMLPyriteTieFlightgroupElement extends Components.PyriteTieFlightgroup, HTMLStencilElement {}
  var HTMLPyriteTieFlightgroupElement: {
    prototype: HTMLPyriteTieFlightgroupElement;
    new (): HTMLPyriteTieFlightgroupElement;
  };

  interface HTMLPyriteTieFlightgroupsElement extends Components.PyriteTieFlightgroups, HTMLStencilElement {}
  var HTMLPyriteTieFlightgroupsElement: {
    prototype: HTMLPyriteTieFlightgroupsElement;
    new (): HTMLPyriteTieFlightgroupsElement;
  };

  interface HTMLPyriteTiePltElement extends Components.PyriteTiePlt, HTMLStencilElement {}
  var HTMLPyriteTiePltElement: {
    prototype: HTMLPyriteTiePltElement;
    new (): HTMLPyriteTiePltElement;
  };

  interface HTMLPyriteTiePreMissionQuestionsElement extends Components.PyriteTiePreMissionQuestions, HTMLStencilElement {}
  var HTMLPyriteTiePreMissionQuestionsElement: {
    prototype: HTMLPyriteTiePreMissionQuestionsElement;
    new (): HTMLPyriteTiePreMissionQuestionsElement;
  };

  interface HTMLPyriteTieScoreElement extends Components.PyriteTieScore, HTMLStencilElement {}
  var HTMLPyriteTieScoreElement: {
    prototype: HTMLPyriteTieScoreElement;
    new (): HTMLPyriteTieScoreElement;
  };

  interface HTMLPyriteXvtPltElement extends Components.PyriteXvtPlt, HTMLStencilElement {}
  var HTMLPyriteXvtPltElement: {
    prototype: HTMLPyriteXvtPltElement;
    new (): HTMLPyriteXvtPltElement;
  };

  interface HTMLTacBattleElement extends Components.TacBattle, HTMLStencilElement {}
  var HTMLTacBattleElement: {
    prototype: HTMLTacBattleElement;
    new (): HTMLTacBattleElement;
  };

  interface HTMLTacBattleTypesElement extends Components.TacBattleTypes, HTMLStencilElement {}
  var HTMLTacBattleTypesElement: {
    prototype: HTMLTacBattleTypesElement;
    new (): HTMLTacBattleTypesElement;
  };

  interface HTMLTacBattlesElement extends Components.TacBattles, HTMLStencilElement {}
  var HTMLTacBattlesElement: {
    prototype: HTMLTacBattlesElement;
    new (): HTMLTacBattlesElement;
  };
  interface HTMLElementTagNameMap {
    'app-home': HTMLAppHomeElement;
    'app-root': HTMLAppRootElement;
    'pyrite-mission': HTMLPyriteMissionElement;
    'pyrite-mission-tabs': HTMLPyriteMissionTabsElement;
    'pyrite-resource': HTMLPyriteResourceElement;
    'pyrite-resources': HTMLPyriteResourcesElement;
    'pyrite-scratch': HTMLPyriteScratchElement;
    'pyrite-tie-briefing': HTMLPyriteTieBriefingElement;
    'pyrite-tie-flightgroup': HTMLPyriteTieFlightgroupElement;
    'pyrite-tie-flightgroups': HTMLPyriteTieFlightgroupsElement;
    'pyrite-tie-plt': HTMLPyriteTiePltElement;
    'pyrite-tie-pre-mission-questions': HTMLPyriteTiePreMissionQuestionsElement;
    'pyrite-tie-score': HTMLPyriteTieScoreElement;
    'pyrite-xvt-plt': HTMLPyriteXvtPltElement;
    'tac-battle': HTMLTacBattleElement;
    'tac-battle-types': HTMLTacBattleTypesElement;
    'tac-battles': HTMLTacBattlesElement;
  }
}

declare namespace LocalJSX {
  interface AppHome extends JSXBase.HTMLAttributes<HTMLAppHomeElement> {}
  interface AppRoot extends JSXBase.HTMLAttributes<HTMLAppRootElement> {}
  interface PyriteMission extends JSXBase.HTMLAttributes<HTMLPyriteMissionElement> {
    'file'?: string;
    'name'?: string;
    'nr'?: string;
    'plt'?: string;
    'sub'?: string;
  }
  interface PyriteMissionTabs extends JSXBase.HTMLAttributes<HTMLPyriteMissionTabsElement> {}
  interface PyriteResource extends JSXBase.HTMLAttributes<HTMLPyriteResourceElement> {
    'name'?: string;
  }
  interface PyriteResources extends JSXBase.HTMLAttributes<HTMLPyriteResourcesElement> {}
  interface PyriteScratch extends JSXBase.HTMLAttributes<HTMLPyriteScratchElement> {}
  interface PyriteTieBriefing extends JSXBase.HTMLAttributes<HTMLPyriteTieBriefingElement> {
    'mission'?: Mission;
  }
  interface PyriteTieFlightgroup extends JSXBase.HTMLAttributes<HTMLPyriteTieFlightgroupElement> {
    'flightGroup'?: FlightGroup;
  }
  interface PyriteTieFlightgroups extends JSXBase.HTMLAttributes<HTMLPyriteTieFlightgroupsElement> {
    'mission'?: Mission;
  }
  interface PyriteTiePlt extends JSXBase.HTMLAttributes<HTMLPyriteTiePltElement> {
    'file'?: string;
  }
  interface PyriteTiePreMissionQuestions extends JSXBase.HTMLAttributes<HTMLPyriteTiePreMissionQuestionsElement> {
    'mission'?: Mission;
  }
  interface PyriteTieScore extends JSXBase.HTMLAttributes<HTMLPyriteTieScoreElement> {
    'mission'?: Mission;
  }
  interface PyriteXvtPlt extends JSXBase.HTMLAttributes<HTMLPyriteXvtPltElement> {
    'ehtc'?: boolean;
    'file'?: string;
  }
  interface TacBattle extends JSXBase.HTMLAttributes<HTMLTacBattleElement> {
    'nr'?: string;
    'plt'?: string;
    'sub'?: string;
  }
  interface TacBattleTypes extends JSXBase.HTMLAttributes<HTMLTacBattleTypesElement> {}
  interface TacBattles extends JSXBase.HTMLAttributes<HTMLTacBattlesElement> {
    'plt'?: string;
    'sub'?: string;
  }

  interface IntrinsicElements {
    'app-home': AppHome;
    'app-root': AppRoot;
    'pyrite-mission': PyriteMission;
    'pyrite-mission-tabs': PyriteMissionTabs;
    'pyrite-resource': PyriteResource;
    'pyrite-resources': PyriteResources;
    'pyrite-scratch': PyriteScratch;
    'pyrite-tie-briefing': PyriteTieBriefing;
    'pyrite-tie-flightgroup': PyriteTieFlightgroup;
    'pyrite-tie-flightgroups': PyriteTieFlightgroups;
    'pyrite-tie-plt': PyriteTiePlt;
    'pyrite-tie-pre-mission-questions': PyriteTiePreMissionQuestions;
    'pyrite-tie-score': PyriteTieScore;
    'pyrite-xvt-plt': PyriteXvtPlt;
    'tac-battle': TacBattle;
    'tac-battle-types': TacBattleTypes;
    'tac-battles': TacBattles;
  }
}

export { LocalJSX as JSX };


declare module "@stencil/core" {
  export namespace JSX {
    interface IntrinsicElements extends LocalJSX.IntrinsicElements {}
  }
}

