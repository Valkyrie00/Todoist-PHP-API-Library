<?php
/**
 * Todoist-PHP-API-Library
 * A PHP client library that provides a native interface to the official Todoist REST API.
 *
 * @author  Fabian Beiner <fb@fabianbeiner.de>
 * @license https://opensource.org/licenses/MIT MIT
 *
 * @see     https://github.com/FabianBeiner/Todoist-PHP-API-Library
 */

namespace FabianBeiner\Todoist;

/**
 * Trait TodoistProjectsTrait.
 */
trait TodoistProjectsTrait
{
    /**
     * Returns an array containing all user projects.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all active user projects, or false on failure.
     */
    public function getAllProjects()
    {
        /** @var object $result Result of the GET request. */
        $result = $this->get('projects');

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Creates a new project and returns it as an array.
     *
     * @param string $projectName        The name of the new project.
     * @param array  $optionalParameters Optional parameters, see
     *                                   https://developer.todoist.com/rest/v2#create-a-new-project
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool An array containing the values of the new project, or false on failure.
     */
    public function createProject(string $projectName, array $optionalParameters = [])
    {
        if ( ! strlen($projectName)) {
            return false;
        }

        // Only allow valid optional parameters.
        $validParameters = [
            'color',
            'parent_id',
            'view_style',
            'is_favorite',
        ];
        $filteredParameters = array_intersect_key($optionalParameters, array_flip($validParameters));

        $postData = $this->preparePostData(array_merge(['name' => $projectName], $filteredParameters));

        /** @var object $result Result of the POST request. */
        $result = $this->post('projects', $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Returns an array containing a project object related to the given ID.
     *
     * @param string $projectId The ID of the project.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing the project data related to the given id, or false on failure.
     */
    public function getProject(string $projectId)
    {
        if ( ! $this->validateId($projectId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('projects/' . $projectId);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Updates the project for the given ID.
     *
     * @param string $projectId The ID of the project.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     *
     * @return array|bool True on success, false on failure.
     */
    public function updateProject(string $projectId, array $optionalParameters = [])
    {
        if ( ! $this->validateId($projectId)) {
            return false;
        }

        // Only allow valid optional parameters.
        $validParameters = [
            'name',
            'color',
            'view_style',
            'is_favorite',
        ];
        $filteredParameters = array_intersect_key($optionalParameters, array_flip($validParameters));

        $postData = $this->preparePostData($filteredParameters);

        /** @var object $result Result of the POST request. */
        $result = $this->post('projects/' . $projectId, $postData);

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }

    /**
     * Deletes a project.
     *
     * @param string $projectId The ID of the project.
     *
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return bool True on success, false on failure.
     */
    public function deleteProject(string $projectId): bool
    {
        if ( ! $this->validateId($projectId)) {
            return false;
        }

        /** @var object $result Result of the DELETE request. */
        $result = $this->delete('projects/' . $projectId);

        return 204 === $result->getStatusCode();
    }

    /**
     * Returns an array containing all collaborators of a shared project.
     *
     * @param string $projectId The ID of the project.
     *
     * @throws \FabianBeiner\Todoist\TodoistException
     * @throws \GuzzleHttp\Exception\GuzzleException
     *
     * @return array|bool An array containing all collaborators of a shared project.
     */
    public function getAllCollaborators(string $projectId)
    {
        if ( ! $this->validateId($projectId)) {
            return false;
        }

        /** @var object $result Result of the GET request. */
        $result = $this->get('projects/' . $projectId . '/collaborators');

        return $this->handleResponse($result->getStatusCode(), $result->getBody()->getContents());
    }
}
