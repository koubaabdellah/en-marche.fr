import React, {PropTypes} from 'react';
import Measure from './Measure';
import Project from './Project';

export default class SearchResults extends React.Component {
    render() {
        return <div className="programmatic-foundation__search-results">
            {(this.props.measures.length || this.props.projects.length) ?
                this.renderResultContent() :
                this.renderEmptyResultMessage()
            }
        </div>;
    }

    renderResultContent() {
        return (
            <div>
                <div className="measures">
                    <h2>Mesures</h2>

                    <div className="programmatic-foundation__children programmatic-foundation__measures">
                        {this.props.measures.map((measure, index) => {
                            return <Measure
                                key={index}
                                measure={measure}
                                preventAutoExpand={true}
                                parentSectionIdentifierParts={measure.parentSectionIdentifierParts}
                            />
                        })}
                    </div>
                </div>

                <div className="projects">
                    <h2>Projets illustratifs</h2>

                    <div className="programmatic-foundation__children programmatic-foundation__projects">
                        {this.props.projects.map((project, index) => {
                            return <Project
                                key={index}
                                project={project}
                                preventAutoExpand={true}
                            />
                        })}
                    </div>
                </div>
            </div>
        );
    }

    renderEmptyResultMessage() {
        return (
            <div className="measures text--center">
                Il n'y a pas de mesure ni de projet associé à votre recherche.<br/>
                Proposez-en un(e) à l'équipe programme en
                écrivant à <a href="mailto:idees@en-marche.fr">idees@en-marche.fr</a>
            </div>
        );
    }
}

SearchResults.propsType = {
    measures: PropTypes.arrayOf(PropTypes.object).isRequired,
    projects: PropTypes.arrayOf(PropTypes.object).isRequired,
};

SearchResults.defaultProps = {
    measures: [],
    projects: [],
};
