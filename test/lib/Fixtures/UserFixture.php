<?php
namespace Omocha\Fixtures;

/**
 * @Entity users
 */
class UserFixture {
	/**
	 * @Id
	 * @Type integer
	 */
	private $id;
	
	/**
	 * @Column username
	 */
	private $name;
	
	/**
	 * @Query "SELECT * FROM accounts WHERE user_id = #{id}"
	 * @Option(map.type) array[]
	 */
	private $accounts;
	
	/**
	 * @Query "SELECT * FROM people WHERE best_friend = %{i} OR name LIKE %{s}"
	 * @Parameter(id)
	 * @Parameter(name:string)
	 */
	private $friends;
	
	/**
	 * @StatementId users.findComments
	 * @Self
	 * @Parameter true
	 * @Option
	 */
	private $comments;
	
	/**
	 * @Eval (. (#name) " has a good karma")
	 */
	private $message;
	
	/**
	 * @If (> (count (#comments)) 10)
	 * @StatementId users.getLastComments
	 * @Parameter 10
	 */
	private $chatter;
	
	/**
	 * @Query "SELECT * FROM comments WHERE user_id = %{i} AND value = %{f}"
	 * @Parameter(id)
	 * @Parameter 7.5
	 */
	private $bestContributions;
}
?>